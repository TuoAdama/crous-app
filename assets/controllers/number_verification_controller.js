import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['counter', 'counterParent'];
    static values = {
        seconds: Number
    }
    connect() {
        if (this.secondsValue != 0){
            const id = setInterval(() => {
                const time = this.getTime(this.secondsValue);
                this.counterTarget.textContent = `${time.minutes}:${time.seconds}`
                this.secondsValue--;
                if (this.secondsValue === 0){
                    clearInterval(id);
                    this.alertExpiredMsg();
                }
            }, 1000);
        }
    }


    getTime(seconds){
        if (seconds < 0){
            return {minutes: 0, seconds: 0}
        }
        let mins = Math.floor(seconds/60);
        let secs = seconds - mins*60;
        if (secs < 10){
            secs = `0${secs}`;
        }
        if (mins < 10){
            mins = `0${mins}`;
        }
        return {minutes: mins, seconds: secs}
    }

    alertExpiredMsg(){
        this.counterParentTarget.textContent = "Code de vérification expiré"
        this.counterParentTarget.style.color = 'red';
    }
}