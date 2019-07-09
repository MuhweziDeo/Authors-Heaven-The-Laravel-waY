import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { IArticle } from '../../shared/models/articles.model';

@Component({
  selector: 'app-article-list',
  templateUrl: './article-list.component.html',
  styleUrls: ['./article-list.component.css']
})
export class ArticleListComponent implements OnInit {
  @Input() articles: Array<IArticle>;
  @Output() emitArticles = new EventEmitter();
  constructor() { }

  ngOnInit() {
  }

  loadMoreArticles() {
    this.emitArticles.emit();
  }

}
