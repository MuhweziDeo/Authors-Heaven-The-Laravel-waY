import { Component, OnInit } from '@angular/core';
import { ArticleService } from '../article/article.service';
import { IArticle } from '../shared/models/articles.model';

@Component({
  selector: 'app-landing',
  templateUrl: './landing.component.html',
  styleUrls: ['./landing.component.css']
})
export class LandingComponent implements OnInit {
  articles: IArticle[];
  limitStart: number;
  limitEnd: number;
  constructor(
  private articleService: ArticleService
  ) {
    this.limitStart = 0;
    this.limitEnd = 10;
  }

  ngOnInit() {
    this.getArticles();
  }

  loadArticles() {
    this.limitStart = this.limitEnd;
    this.limitEnd = this.limitEnd + 5;
    this.getArticles();
  }

  getArticles() {
    this.articleService.getArticles({limitStart: this.limitStart, limitEnd: this.limitEnd})
    .subscribe(res => {
    if (this.articles) {
      return this.articles = [...this.articles, ...res];
    }
    return this.articles = res;
    });
  }

}
