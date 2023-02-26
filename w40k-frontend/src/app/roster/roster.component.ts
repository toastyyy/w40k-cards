import { Component, OnInit } from '@angular/core';
import RosterService from "../../service/roster.service";
import {ActivatedRoute, Router} from "@angular/router";
import {BehaviorSubject} from "rxjs";
import RosterModel from "../../models/roster.model";
import CardModel from 'src/models/card.model';
import { MatDialog } from '@angular/material/dialog';
import { CardEditorDialogComponent } from '../dialogs/card-editor-dialog/card-editor-dialog.component';
import CardService from "../../service/card.service";

@Component({
  selector: 'app-roster',
  templateUrl: './roster.component.html',
  styleUrls: ['./roster.component.scss']
})
export class RosterComponent implements OnInit {
  private rosterSubject = new BehaviorSubject<RosterModel>(null);
  public roster$ = this.rosterSubject.asObservable();

  private loadingSubject = new BehaviorSubject<boolean>(false);
  public loading$ = this.loadingSubject.asObservable();

  constructor(private rosterService: RosterService, private route: ActivatedRoute, private dialog: MatDialog, private router: Router,
              private cardService: CardService) { }

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      this.loadingSubject.next(true);
      this.rosterService.show(params.get('id')).subscribe(roster => {
        this.loadingSubject.next(false);
        this.rosterSubject.next(roster);
      });
    });
  }

  public editCard(card: CardModel) {
    console.log(['roster', this.rosterSubject.getValue().id, card.id]);
    this.router.navigate(['roster', this.rosterSubject.getValue().id, card.id]);
  }

  public addCard() {
    this.cardService.create({
      title: 'Neue Karte',
      weapons: [],
      units: [],
      abilities: [],
      roster: {
        id: this.rosterSubject.getValue().id
      }
    }).subscribe(result => {
      this.editCard(result);
    });
  }

}
