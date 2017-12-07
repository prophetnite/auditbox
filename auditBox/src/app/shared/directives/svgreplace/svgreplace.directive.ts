import { Directive, OnInit, ElementRef } from '@angular/core';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import 'rxjs/add/operator/map';

@Directive({
    selector: '[svgreplace]'
})
export class SvgreplaceDirective implements OnInit {

    constructor(private element: ElementRef, private http: HttpClient) { }

    ngOnInit() {
        setTimeout(() => {

            let src = this.element.nativeElement.src;

            if (!src || src.indexOf('.svg') < 0) {
                throw "only support for SVG images"; // return /*only support for SVG images*/;
            }
            this.http.get<string>(src, { responseType: 'text' as 'json' })
                .subscribe(data => {
                    this.element.nativeElement.outerHTML = data;
                });

        });
    }

}
