import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ['numberButton'];
    static values = {
        oldNumber: String,
    }
    onChangeNumber(e){
        const currentValue = e.target.value;
        this.numberButtonTarget.disabled = true;
        if (currentValue != this.oldNumberValue){
            this.numberButtonTarget.disabled = false;
        }
    }
}