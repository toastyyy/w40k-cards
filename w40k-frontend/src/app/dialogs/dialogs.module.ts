import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardEditorDialogComponent } from './card-editor-dialog/card-editor-dialog.component';
import { FormsModule } from '@angular/forms';
import { MatInputModule } from '@angular/material/input';



@NgModule({
  declarations: [CardEditorDialogComponent],
  imports: [
    CommonModule,
    FormsModule,
    MatInputModule
  ]
})
export class DialogsModule { }
