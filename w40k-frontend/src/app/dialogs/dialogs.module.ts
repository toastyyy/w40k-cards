import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardEditorDialogComponent } from './card-editor-dialog/card-editor-dialog.component';
import { FormsModule } from '@angular/forms';
import { MatInputModule } from '@angular/material/input';
import { ConfirmDialogComponent } from './confirm-dialog/confirm-dialog.component';
import {MatDialogModule} from "@angular/material/dialog";
import {MatButtonModule} from "@angular/material/button";



@NgModule({
  declarations: [CardEditorDialogComponent, ConfirmDialogComponent],
  imports: [
    CommonModule,
    FormsModule,
    MatInputModule,
    MatDialogModule,
    MatButtonModule
  ]
})
export class DialogsModule { }
