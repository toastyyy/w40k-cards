import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';
import {HttpClientModule} from "@angular/common/http";
import {MatFormFieldModule} from "@angular/material/form-field";
import {MatInputModule} from "@angular/material/input";
import {FormsModule} from "@angular/forms";
import {MatButtonModule} from "@angular/material/button";
import { MAT_COLOR_FORMATS, NgxMatColorPickerModule, NGX_MAT_COLOR_FORMATS } from '@angular-material-components/color-picker';
import { V2Component } from './v2/v2.component';
import {MatSnackBarModule} from "@angular/material/snack-bar";
import {SlickCarouselModule} from "ngx-slick-carousel";
import { RosterComponent } from './roster/roster.component';
import {CardsModule} from "./cards/cards.module";
import { CardDetailComponent } from './card-detail/card-detail.component';
import {MatTabsModule} from '@angular/material/tabs';
import { MediumComponent } from './medium/medium.component';
import { MatIconModule } from '@angular/material/icon';
import {MatExpansionModule} from '@angular/material/expansion';

@NgModule({
  declarations: [
    AppComponent,
    V2Component,
    //V1Component,
    RosterComponent,
    CardDetailComponent,
    MediumComponent
  ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        HttpClientModule,
        NoopAnimationsModule,
        MatFormFieldModule,
        FormsModule,
        MatInputModule,
        MatButtonModule,
        NgxMatColorPickerModule,
        MatSnackBarModule,
        SlickCarouselModule,
        CardsModule,
        MatTabsModule,
        MatIconModule,
        MatExpansionModule,
    ],
  providers: [
    { provide: MAT_COLOR_FORMATS, useValue: NGX_MAT_COLOR_FORMATS }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
