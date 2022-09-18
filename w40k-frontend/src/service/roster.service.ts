import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import RosterModel from "../models/roster.model";
import {environment} from "../environments/environment";
import {HttpClient} from "@angular/common/http";

@Injectable({ providedIn: 'root' })
export default class RosterService {
  public constructor(private http: HttpClient) {
  }

  public show(id): Observable<RosterModel> {
    return this.http.get<RosterModel>(environment.apiUrl + '/roster/' + id);
  }

  public create(rosterData): Observable<RosterModel> {
    return this.http.post<RosterModel>(environment.apiUrl + '/roster', { data: rosterData });
  }

  public update(roster: RosterModel) {
    return this.http.put(environment.apiUrl + '/roster/' + roster.id, roster);
  }
}
