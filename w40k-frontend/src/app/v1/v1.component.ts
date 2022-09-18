import {Component, OnInit, ViewChild} from '@angular/core';
import UnitModel from "../../models/unit.model";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import AttackModel from "../../models/attack.model";
import AbilityModel from "../../models/ability.model";
import InfoModel from "../../models/info.model";

@Component({
  selector: 'app-v1',
  templateUrl: './v1.component.html',
  styleUrls: ['./v1.component.scss']
})
export class V1Component implements OnInit {
  @ViewChild("fileInput") fileInput;

  public renderedContent: string;

  public unit: UnitModel = {
    abilities: [],
    attack: 0,
    attacks: [],
    ballisticSkill: '3+',
    weaponSkill: '3+',
    powerLevel: 5,
    color: '#888888',
    colorDark: '#222222',
    colorLight: '#eeeeee',
    infos: [],
    leadership: 0,
    points: 100,
    movementSpeed: "5''",
    save: '3+',
    image: '',
    strength: '4',
    toughness: 4,
    unitName: '',
    wounds: 2
  };

  public constructor(private http: HttpClient) {
  }
  public ngOnInit() {
  }

  public generate() {
    if(typeof this.unit.colorLight != 'string') {
      this.unit.colorLight = '#' + this.unit.colorLight.hex;
    }
    if(typeof this.unit.color != 'string') {
      this.unit.color = '#' + this.unit.color.hex;
    }
    if(typeof this.unit.colorDark != 'string') {
      this.unit.colorDark = '#' + this.unit.colorDark.hex;
    }

    this.http.post<any>('https://api.w40k.csc.cx/render-card', this.unit, {
      headers: new HttpHeaders({
        'Content-Type': 'application/pdf',
      }),
      observe: 'response',
      responseType: 'arraybuffer' as 'json'
    }).subscribe(response => {
      var blob = new Blob([response.body], {type: "application/pdf"});
      var objectUrl = URL.createObjectURL(blob);
      //window.open(objectUrl);
      let fileLink = document.createElement('a');
      fileLink.href = objectUrl;
      fileLink.download = 'w40k-card.pdf';

      fileLink.click();
    });
  }

  public addAttack() {
    this.unit.attacks.push({
      damage: 1,
      penetration: 0,
      strength: 4,
      distance: "24''",
      name: '',
      type: '',
      abilities: ''
    });
  }

  public chooseImage() {
    this.fileInput.nativeElement.click();
  }

  public addInfo() {
    this.unit.infos.push({
      text: '',
      title: ''
    });
  }

  public addAbility() {
    this.unit.abilities.push({
      name: '',
      text: ''
    });
  }

  public removeAttack(attack: AttackModel) {
    const index = this.unit.attacks.indexOf(attack);
    this.unit.attacks.splice(index, 1);
  }

  public removeAbility(ability: AbilityModel) {
    const index = this.unit.abilities.indexOf(ability);
    this.unit.abilities.splice(index, 1);
  }

  public removeInfo(info: InfoModel) {
    const index = this.unit.infos.indexOf(info);
    this.unit.infos.splice(index, 1);
  }

  public onFileChanged(event) {
    let reader = new FileReader();
    if(event.target.files && event.target.files.length > 0) {
      let file = event.target.files[0];
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.unit.image = reader.result as string;
      };
    }
  }

  public downloadModelData() {
    if(typeof this.unit.colorLight != 'string') {
      this.unit.colorLight = '#' + this.unit.colorLight.hex;
    }
    if(typeof this.unit.color != 'string') {
      this.unit.color = '#' + this.unit.color.hex;
    }
    if(typeof this.unit.colorDark != 'string') {
      this.unit.colorDark = '#' + this.unit.colorDark.hex;
    }
    let element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(JSON.stringify(this.unit)));
    element.setAttribute('download', 'w40k.model');

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
  }

  public loadModelData(event) {
    let reader = new FileReader();
    if(event.target.files && event.target.files.length > 0) {
      let file = event.target.files[0];
      reader.readAsDataURL(file);
      reader.onload = () => {
        let data = (reader.result as string).split(',')[1];
        data = atob(data);
        console.log(data);
        this.unit = JSON.parse(data) as UnitModel;
      };
    }
  }
}
