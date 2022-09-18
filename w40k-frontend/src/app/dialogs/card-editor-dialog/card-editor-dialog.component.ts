import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA } from '@angular/material/dialog';
import CardModel from 'src/models/card.model';

@Component({
  selector: 'app-card-editor-dialog',
  templateUrl: './card-editor-dialog.component.html',
  styleUrls: ['./card-editor-dialog.component.scss']
})
export class CardEditorDialogComponent implements OnInit {

  constructor(@Inject(MAT_DIALOG_DATA) public data: CardEditorDialogModel) {}

  ngOnInit(): void {
  }

}

export class CardEditorDialogModel {
  public constructor(public card: CardModel) {}
}