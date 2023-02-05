import {Injectable} from "@angular/core";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Observable, of} from "rxjs";
import RosterModel from "../models/roster.model";
import {environment} from "../environments/environment";
import CardModel from "../models/card.model";
import { catchError, switchMap } from "rxjs/operators";

@Injectable({ providedIn: 'root' })
export default class CardService {
  public constructor(private http: HttpClient) {
  }

  public show(id): Observable<CardModel> {
    return this.http.get<CardModel>(environment.apiUrl + '/cards/' + id);
  }

  public create(card: CardModel): Observable<CardModel> {
    return this.http.post<CardModel>(environment.apiUrl + '/cards', card);
  }

  public update(card: CardModel) {
    return this.http.put(environment.apiUrl + '/cards/' + card.id, card);
  }

  public delete(cardId: string) {
    return this.http.delete(environment.apiUrl + '/cards/' + cardId).pipe(
      switchMap(r => of(true)),
      catchError(r => of(false))
    );
  }

  public generatePdf(content: string): Observable<Blob> {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/pdf');

    return this.http.post(environment.apiUrl + '/render-card', { content: content }, { headers: headers, responseType: 'blob' as 'json' }).pipe(switchMap((blob: Blob) => {
      const fileURL = URL.createObjectURL(blob);
      window.open(fileURL, '_blank', 'width=1000, height=800');
      return of(blob);
    }));
  }
}
