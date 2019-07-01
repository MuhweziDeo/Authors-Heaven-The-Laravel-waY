import { NgModule } from '@angular/core';
import {
  MatToolbarModule,
  MatCardModule,
  MatChipsModule,
  MatBadgeModule,
  MatButtonModule,
  MatSidenavModule,
  MatIconModule,
  MatListModule
  } from '@angular/material';
import { FlexLayoutModule } from '@angular/flex-layout';

@NgModule({
  declarations: [],
  imports: [
    MatToolbarModule,
    MatCardModule,
    FlexLayoutModule,
    MatChipsModule,
    MatBadgeModule,
    MatButtonModule,
    MatSidenavModule,
    MatIconModule,
    MatListModule
  ],
  exports: [
    MatToolbarModule,
    MatCardModule,
    FlexLayoutModule,
    MatChipsModule,
    MatBadgeModule,
    MatButtonModule,
    MatSidenavModule,
    MatIconModule,
    MatListModule
  ]
})
export class AngularMaterialModule { }
