import {AfterViewInit, Component, ElementRef, Input, OnInit, ViewChild} from '@angular/core';
import CardModel from "../../../models/card.model";
import {DomSanitizer} from "@angular/platform-browser";

@Component({
  selector: 'app-unit-basic',
  templateUrl: './unit-basic.component.html',
  styleUrls: ['./unit-basic.component.scss']
})
export class UnitBasicComponent implements OnInit, AfterViewInit {
  @Input() card: CardModel;
  @Input() size = 20;
  @Input() hideSidebar = false;
  @Input() backsideMode: 'none'|'frontpage'|'backside' = 'none';

  constructor(private elem: ElementRef, private sanitizer: DomSanitizer) { }

  ngOnInit(): void {

  }

  ngAfterViewInit(): void {
    //this.inlineStyles();
  }

  inlineStyles() {
      if(this.elem) {
        this.recursiveInlineStyling(this.elem.nativeElement);
      } else {
        setTimeout(() => {
          this.inlineStyles();
        }, 1000);
      }
  }

  public bypassSecurityStyle(str) {
    return this.sanitizer.bypassSecurityTrustStyle(str);
  }

  private applyStyle(el) {
      let s = getComputedStyle(el);
      for (let key in s) {
          try {
            if(['color', 'border', 'border-radius', 'background', 'background-color', 'position',
            'top', 'left', 'bottom', 'right', 'padding', 'padding-top', 'padding-left', 'padding-right',
            'padding-bottom', 'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
            'font-size', 'line-height', 'background-size', 'background-position', 'width', 'height', 'max-width',
            'max-height', 'float', 'display', 'text-aign', 'overflow', 'border-width', 'border-style', 'border-color',
            '-webkit-clip-path', 'clip-path', 'font-weight', 'font-family', 'text-shadow', 'opacity', 'transform',
            'content', 'white-space', 'text-overflow'
            ].indexOf(s[key]) >= 0) {
              el.style[s[key]] = s.getPropertyValue(s[key]);
            }

          } catch(e) {}

      }
  }

  private recursiveInlineStyling(el) {
    this.applyStyle(el);
    for(let c of el.children) {
      this.recursiveInlineStyling(c);
    }
  }
}
