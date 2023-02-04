import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";

@Component({
  selector: 'app-confirm-dialog',
  templateUrl: './confirm-dialog.component.html',
  styleUrls: ['./confirm-dialog.component.scss']
})
export class ConfirmDialogComponent implements OnInit {
  public title: string = 'Bist du sicher?';
  public text: string = 'Möchtest du die Aktion wirklich durchführen?';

  constructor(@Inject(MAT_DIALOG_DATA) public data: any, private dialog: MatDialogRef<ConfirmDialogComponent>) {
    if(this.data.title) {
      this.title = this.data.title;
    }
    if(this.data.text) {
      this.text = this.data.text;
    }
  }

  ngOnInit(): void {
  }

  public confirm() {
    this.dialog.close(true);
  }

  public dismiss() {
    this.dialog.close(false);
  }

}
