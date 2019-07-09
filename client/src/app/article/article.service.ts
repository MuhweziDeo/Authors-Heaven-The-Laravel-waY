import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import {IArticle} from '../shared/models/articles.model';
import { environment } from '../../environments/environment';


@Injectable({
  providedIn: 'root'
})
export class ArticleService {
  url = `${environment.apiUrl}articles`;
  constructor(
  private http: HttpClient
  ) { }

  public getArticles(limit: ILimit): Observable<IArticle[]> {
  return this.http.get<{data: IArticle[]}>(`${this.url}?start=${limit.limitStart}&end=${limit.limitEnd}`)
  .pipe(
    map((data) => data.data)
  );
  }
}

export interface ILimit {
  limitStart: number;
  limitEnd: number;
}
