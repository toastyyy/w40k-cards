import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UnitBasicComponent } from './unit-basic/unit-basic.component';



@NgModule({
    declarations: [UnitBasicComponent],
    exports: [
        UnitBasicComponent
    ],
    imports: [
        CommonModule
    ]
})
export class CardsModule { }
