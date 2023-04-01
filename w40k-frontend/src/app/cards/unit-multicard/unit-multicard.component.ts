import {Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import CardModel from "../../../models/card.model";

@Component({
  selector: 'app-unit-multicard',
  templateUrl: './unit-multicard.component.html',
  styleUrls: ['./unit-multicard.component.scss']
})
export class UnitMulticardComponent implements OnInit, OnChanges {
  @Input() card: CardModel;
  @Input() size = 20;

  private cardLeft: CardModel;
  private cardRight: CardModel;

  constructor() { }

  ngOnInit(): void {
    this.generateSubCards();
  }

  public generateSubCards() {
    let leftCard = JSON.parse(JSON.stringify(this.card)) as CardModel;
    leftCard.abilities.length = 0;
    leftCard.abilities.length = 0;
    leftCard.keywords.length = 0;

    let rightCard = JSON.parse(JSON.stringify(this.card)) as CardModel;
    rightCard.weapons.length = 0;
    rightCard.units.length = 0;
    this.cardLeft = leftCard;
    this.cardRight = rightCard;
  }

  ngOnChanges(changes: SimpleChanges) {
    this.generateSubCards();
  }

}
