import { NgModule } from '@angular/core';
import {
  MatToolbarModule,
  MatCardModule,
  MatChipsModule,
  MatBadgeModule,
  MatButtonModule,
  MatSidenavModule,
  MatIconModule,
  MatListModule,
  MatDividerModule,
  MatFormFieldModule,
  MatInputModule
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
    MatListModule,
    MatCardModule,
    MatDividerModule,
    MatFormFieldModule,
    MatInputModule
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
    MatListModule,
    MatCardModule,
    MatDividerModule,
    MatFormFieldModule,
    MatInputModule
  ]
})
export class AngularMaterialModule { }
