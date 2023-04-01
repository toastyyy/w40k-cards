import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import {BehaviorSubject, Subscription} from 'rxjs';
import CardModel from 'src/models/card.model';
import CardService from 'src/service/card.service';
import { skip, debounceTime } from 'rxjs/operators';
import { MatSnackBar } from '@angular/material/snack-bar';
import UnitModel from "../../models/unit.model";
import WeaponModel from "../../models/weapon.model";
import AbilityModel from "../../models/ability.model";
import {MatDialog} from "@angular/material/dialog";
import {ConfirmDialogComponent} from "../dialogs/confirm-dialog/confirm-dialog.component";
import {UnitMulticardComponent} from "../cards/unit-multicard/unit-multicard.component";

@Component({
  selector: 'app-card-detail',
  templateUrl: './card-detail.component.html',
  styleUrls: ['./card-detail.component.scss']
})
export class CardDetailComponent implements OnInit {
  private cardSubject = new BehaviorSubject<CardModel>(null);
  public card$ = this.cardSubject.asObservable();

  private loadingSubject = new BehaviorSubject<boolean>(false);
  public loading$ = this.loadingSubject.asObservable();

  private downloadingSubject = new BehaviorSubject<boolean>(false);
  public downloading$ = this.downloadingSubject.asObservable();

  public rosterId;

  private changedSubject = new BehaviorSubject<any>(null);
  public changed$ = this.changedSubject.asObservable();

  private savingSubject = new BehaviorSubject<boolean>(false);
  public saving$ = this.savingSubject.asObservable();
  public size;

  private saveSubscription: Subscription;

  @ViewChild("multiCardContainer") multiCardContainer: UnitMulticardComponent;
  @ViewChild("multiCardContainer", { read: ElementRef }) multiCardContainerNative: ElementRef;
  @ViewChild("normalCardContainer", { read: ElementRef }) normalCardContainer: ElementRef;

  constructor(private router: Router, private route: ActivatedRoute, private cardService: CardService, private snackBar: MatSnackBar, private dialog: MatDialog) { }

  ngOnInit(): void {
    this.recalculateCardSize();
    window.onresize = () => {
      this.recalculateCardSize();
    };

    this.route.paramMap.subscribe(params => {
        let cardId = params.get("cardId");
        this.rosterId = params.get("rosterId");
        this.loadingSubject.next(true);
        this.cardService.show(cardId).subscribe(result => {
            this.cardSubject.next(result);
            this.loadingSubject.next(false);
        }, error => {
          this.cardSubject.next(null);
          this.loadingSubject.next(false);
        });
    });

    this.changed$.pipe(skip(1), debounceTime(500)).subscribe(result => {
        this.multiCardContainer.generateSubCards();
        this.saveChanges();
    });
  }

  recalculateCardSize() {
    if(window.innerWidth >= 1600) {
      this.size = window.innerWidth / 50;
    } else if (window.innerWidth >= 640) {
      this.size = window.innerWidth / 40;
    } else {
      this.size = window.innerWidth / 26;
    }
  }

  changed() {
      this.changedSubject.next(this.cardSubject.getValue());
  }

  saveChanges() {
      if(this.saveSubscription) { this.saveSubscription.unsubscribe(); }
      this.saveSubscription = this.cardService.update(this.cardSubject.getValue()).subscribe(result => {
          this.snackBar.open('Änderungen gespeichert', null, { duration: 2000 });
          //this.cardSubject.next(result as any);
          this.savingSubject.next(false);
      }, error => {
          this.snackBar.open('Fehler beim Speichern', null, { duration: 2000 });
          this.savingSubject.next(false);
      });
  }

  delete() {
    this.cardService.delete(this.cardSubject.getValue().id).subscribe(result => {
      if(result) {
          this.snackBar.open('Karte entfernt', null, { duration: 2000 });
          this.router.navigate(['roster', this.rosterId]);
      }
    });
  }

  public applyStyle() {
    let dialogRef = this.dialog.open(ConfirmDialogComponent, {
      data: {
        title: 'Stil anwenden?',
        text: 'Damit werden vorhandene Stileinstellungen im Roster unwiderruflich überschrieben. Fortfahren?'
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if(result) {
        this.cardService.applyStyle(this.cardSubject.getValue().id).subscribe(r => {
            if(r) {
              this.snackBar.open('Stil übernommen', null, { duration: 3000 });
            } else {
              this.snackBar.open('Ein Fehler ist aufgetreten', null, { duration: 3000 });
            }
        });
      }
    });
  }

  public showPDF() {
    this.downloadingSubject.next(true);
    let html = null;
    if(this.cardSubject.getValue().big) {
      html = this.multiCardContainerNative.nativeElement.innerHTML;
    } else {
      html = this.normalCardContainer.nativeElement.innerHTML;
    }

    let headContent = document.getElementsByTagName('head')[0].innerHTML;

    let fullHtml = '<!DOCTYPE html><html><head>' + headContent + '</head><body>' + html + '</body></html>';
    fullHtml = fullHtml
      .replace('--base-size: 20px;', '')
      .replace(/var\(--base-size\)/gm, '40px')
      .replace('--bg-color: rgba(255, 0, 0, 0.3);', '')
      .replace(/var\(--bg-color\)/gm, 'rgba(255, 0, 0, 0.3)')
      //.replace('--border-color: #222;', '')
      //.replace(/var\(--border-color\)/gm, this.cardSubject.getValue().borderColor)
      .replace('--text-color: #fff;', '')
      .replace(/var\(--text-color\)/gm, this.cardSubject.getValue().textColor)
      ;

    this.cardService.generatePdf(fullHtml, this.cardSubject.getValue()).subscribe(result => {
      this.downloadingSubject.next(false);
    }, error => {
      this.downloadingSubject.next(false);
    });
  }

  public addUnit() {
    let card = this.cardSubject.getValue();
    card.units.push({
      attacks: 1,
      leadership: 7,
      ballisticSkill: '3+',
      weaponSkill: '3+',
      name: 'Name',
      movementSpeed: '6\'\'',
      points: 0,
      save: '3+',
      strength: '4',
      wounds: 1,
      toughness: 4
    });
    this.cardSubject.next(card);

    this.changedSubject.next(card);
  }

  public removeUnit(unit: UnitModel) {
    let card = this.cardSubject.getValue();
    let index = card.units.indexOf(unit);
    if(index >= 0) {
      card.units.splice(index, 1);
      this.cardSubject.next(card);
      this.changedSubject.next(card);
    }
  }

  public addWeapon() {
    let card = this.cardSubject.getValue();
    card.weapons.push({
      id: null,

    });
    this.cardSubject.next(card);
    this.changedSubject.next(card);
  }

  public removeWeapon(weapon: WeaponModel) {
    let card = this.cardSubject.getValue();
    let index = card.weapons.indexOf(weapon);
    if(index >= 0) {
      card.weapons.splice(index, 1);
      this.cardSubject.next(card);
      this.changedSubject.next(card);
    }
  }

  public addAbility() {
    let card = this.cardSubject.getValue();
    card.abilities.push({
      name: '',
      description: ''
    });
    this.cardSubject.next(card);
    this.changedSubject.next(card);
  }

  public removeAbility(ability: AbilityModel) {
    let card = this.cardSubject.getValue();
    let index = card.abilities.indexOf(ability);
    if(index >= 0) {
      card.abilities.splice(index, 1);
      this.cardSubject.next(card);
      this.changedSubject.next(card);
    }
  }
}
