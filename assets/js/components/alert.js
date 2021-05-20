/**
 * JS alert
 */
 export class Alert extends HTMLElement {
    connectedCallback(){
        var alertType = this.dataset.type;
        var content = this.dataset.content;
        var svg = '';
        switch (alertType) {
            case "error":
                svg = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x" width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="9" />
                            <path d="M10 10l4 4m0 -4l-4 4" />
                        </svg>`
                    ;
                break;
            case "warning":
                svg = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 9v2m0 4v.01" />
                            <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                        </svg>`
                    ;
                break;
            case "success":
                svg = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="9" />
                            <path d="M9 12l2 2l4 -4" />
                        </svg>`
                    ;
                break;
            default:
                svg = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                            <polyline points="11 12 12 12 12 16 13 16" />
                        </svg>`
                    ;
                break;
        }
        
        this.innerHTML = `
                        <div class="alert alert-${alertType}">
                            <div class="alert-content">
                            <span>
                                ${svg}         
                            </span>
                                <p>${content}</p>
                            </div>
                        <div class="alert-progress-bar"></div>
                    </div>
        `;

        var progressBar = this.querySelector(".alert-progress-bar")
        progressBar.addEventListener('animationend', () => {
            this.remove()
        })
    }
 }

 export function generateAlert(type, content){
    var alert = document.createElement('js-alert')
    alert.dataset.type = type
    alert.dataset.content = content
    document.getElementById('js-alert').appendChild(alert);
 }