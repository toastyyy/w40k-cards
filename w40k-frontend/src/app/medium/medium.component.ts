import { Component, EventEmitter, Input, OnInit, Output, ViewChild } from '@angular/core';
import MediumModel from 'src/models/medium.model';

@Component({
  selector: 'app-medium',
  templateUrl: './medium.component.html',
  styleUrls: ['./medium.component.scss']
})
export class MediumComponent implements OnInit {
  @Output() changed = new EventEmitter<MediumModel>();
  @ViewChild("file") fileUploader;
  @Input() medium: MediumModel;

  constructor() { }

  ngOnInit(): void {
  }

  showUploader() {
    this.fileUploader.nativeElement.click();
  }

  deleteMedia() {
    this.medium = null;
    this.fileUploader.nativeElement.value = null;
    this.changed.emit(this.medium);
  }

  previewImage(files) {
    if (files.length === 0)
      return;

    const mimeType = files[0].type;
    const reader = new FileReader();
    const imagePath = files;
    reader.readAsDataURL(files[0]);
    reader.onload = (_event) => {
      this.medium = {
        content: reader.result.toString(),
        name: this.fileUploader.nativeElement.value.split(/(\\|\/)/g).pop()
      };
      this.changed.emit(this.medium);
    };
  }

}
