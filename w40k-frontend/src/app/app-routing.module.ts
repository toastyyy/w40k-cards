import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {V2Component} from "./v2/v2.component";
import {RosterComponent} from "./roster/roster.component";
import { DialogsModule } from './dialogs/dialogs.module';
import { MatDialogModule } from '@angular/material/dialog';
import { CardDetailComponent } from './card-detail/card-detail.component';


const routes: Routes = [
  { path: '', component: V2Component },
  { path: 'roster/:id', component: RosterComponent },
  { path: 'roster/:rosterId/:cardId', component: CardDetailComponent },
  //{ path: 'old', component: V1Component }
];

@NgModule({
  imports: [RouterModule.forRoot(routes), DialogsModule, MatDialogModule],
  exports: [RouterModule]
})
export class AppRoutingModule { }
