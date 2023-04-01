import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnitMulticardComponent } from './unit-multicard.component';

describe('UnitMulticardComponent', () => {
  let component: UnitMulticardComponent;
  let fixture: ComponentFixture<UnitMulticardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnitMulticardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnitMulticardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
