import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UnitBasicComponent } from './unit-basic/unit-basic.component';
import { UnitMulticardComponent } from './unit-multicard/unit-multicard.component';



@NgModule({
    declarations: [UnitBasicComponent, UnitMulticardComponent],
    exports: [
        UnitBasicComponent,
        UnitMulticardComponent
    ],
    imports: [
        CommonModule
    ]
})
export class CardsModule { }
