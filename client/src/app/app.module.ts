import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { AngularMaterialModule } from './angular-material.module';
import { NavigationComponent } from './shared/navigation/navigation.component';
import { LayoutModule } from '@angular/cdk/layout';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FooterComponent } from './shared/footer/footer.component';
import { ArticleCardComponent } from './article/article-card/article-card.component';
import { ArticleListComponent } from './article/article-list/article-list.component';
import { TagsComponent } from './tags/tags.component';
import { LatestArticlesComponent } from './article/latest-articles/latest-articles.component';
import { SearchComponent } from './shared/search/search.component';
import { BannerComponent } from './shared/banner/banner.component';

@NgModule({
  declarations: [
    AppComponent,
    NavigationComponent,
    FooterComponent,
    ArticleCardComponent,
    ArticleListComponent,
    TagsComponent,
    LatestArticlesComponent,
    SearchComponent,
    BannerComponent
  ],
  imports: [
    BrowserModule,
    AngularMaterialModule,
    LayoutModule,
    BrowserAnimationsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
