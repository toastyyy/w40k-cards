import {Component, OnInit, ViewChild} from '@angular/core';
import UnitModel from "../../models/unit.model";
import {MatSnackBar} from "@angular/material/snack-bar";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {SlickCarouselComponent} from "ngx-slick-carousel";
import {environment} from "../../environments/environment";
import RosterService from "../../service/roster.service";
import {Router} from "@angular/router";

@Component({
  selector: 'app-v2',
  templateUrl: './v2.component.html',
  styleUrls: ['./v2.component.scss']
})
export class V2Component implements OnInit {
  public rosterData;
  public loaded = false;
  public generating = false;
  public fileName = '';

  public imageGeneratorIndex = 0;
  public cards = [];
  public rosterKey: string;

  @ViewChild("slick") slick: SlickCarouselComponent;

  public config = {
    arrows: false,
    dots: false,
    slidesToShow: 1,
    draggable: false,
    swipe: false,
    touchMove: false
  }

  constructor(private snackBar: MatSnackBar, private http: HttpClient, private rosterService: RosterService, private router: Router) { }

  ngOnInit(): void {
    this.rosterData = null;
  }

  loadRosterData(event) {
    let reader = new FileReader();
    if(event.target.files && event.target.files.length > 0) {
      this.loaded = false;
      this.fileName = '';
      let file = event.target.files[0];
      if(!file.name.endsWith('.rosz')) {
        this.snackBar.open('Keine gültige Roster-Datei (.rosz)', null, {
          duration: 3000
        });
        return;
      }
      this.fileName = file.name;

      reader.readAsDataURL(file);
      reader.onload = () => {
        let data = (reader.result as string).split(',')[1];
        this.rosterData = data;
        this.loaded = true;
        this.slick.slickNext();
      };
    }
  }

  submit() {
    this.http.post<any>(environment.apiUrl + '/roster', { data: this.rosterData }, {
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
      fileLink.download = 'w40k-cards.pdf';

      fileLink.click();
    });
  }

  renderCallback() {
    if(this.imageGeneratorIndex >= this.cards.length) {
      this.afterRenderingCallback();
      return;
    }
    this.http.post<any>(environment.apiUrl + '/render-cards-pdf', { roster_key: this.rosterKey, path: this.cards[this.imageGeneratorIndex] }).subscribe(result => {
      this.imageGeneratorIndex++;
      this.renderCallback();
    });
  }

  afterRenderingCallback() {
    this.slick.slickNext();
  }

  generate() {
    this.generating = true;
    this.rosterService.create(this.rosterData).subscribe(result => {
        this.router.navigate(['/roster', result.id]);
    });
  }

  download() {
    this.http.get<any>(environment.apiUrl + '/download-roster/' + this.rosterKey, {
      headers: new HttpHeaders({
        'Content-Type': 'application/zip',
      }),
      observe: 'response',
      responseType: 'arraybuffer' as 'json'
    }).subscribe(response => {
      var blob = new Blob([response.body], {type: "application/zip"});
      var objectUrl = URL.createObjectURL(blob);
      //window.open(objectUrl);
      let fileLink = document.createElement('a');
      fileLink.href = objectUrl;
      fileLink.download = 'cards.zip';

      fileLink.click();
    });
  }

  test() {
    this.http.post<any>(environment.apiUrl + '/test', { data: this.rosterData }).subscribe(response => {
      console.log(response);
    });
  }

}
