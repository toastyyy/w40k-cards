import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnitBasicComponent } from './unit-basic.component';

describe('UnitBasicComponent', () => {
  let component: UnitBasicComponent;
  let fixture: ComponentFixture<UnitBasicComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnitBasicComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnitBasicComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
