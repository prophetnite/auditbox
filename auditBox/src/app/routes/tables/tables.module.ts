import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';

import { SharedModule } from '../../shared/shared.module';
import { ClassicComponent } from './classic/classic.component';
import { DatatablesComponent } from './datatables/datatables.component';

const routes: Routes = [
    { path: 'classic', component: ClassicComponent },
    { path: 'datatables', component: DatatablesComponent }
];

@NgModule({
    imports: [
        SharedModule,
        RouterModule.forChild(routes),
        NgxDatatableModule
    ],
    declarations: [
        ClassicComponent,
        DatatablesComponent
    ],
    providers: [],
    exports: [
        RouterModule
    ]
})
export class TablesModule { }