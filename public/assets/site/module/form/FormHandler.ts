import { StrategyFactory } from './strategies/AjaxApiStrategyFactory';
import { spinnerTemplate, errorTemplate } from './helpers';

export interface NormalizedApiResponse {
    success: boolean;
    message: string;
    data?: any;
    details?: string[];
}

export interface FormData {
    [key: string]: any;
}

export interface ApiStrategy {
    submit(data: FormData): Promise<any>;
    handleResponse(response: any): NormalizedApiResponse;
    handleError(error: any): NormalizedApiResponse;
}

/**
 * FormHandler class decouples UI logic from 3rd party API specifics
 * See StrategyFactory for supported APIs
 */
export class FormHandler {
    private form: HTMLFormElement;
    private strategy: ApiStrategy;
    private submitButton: HTMLButtonElement;

    private loadingClass: string = 'is-loading';

    constructor(formElement: HTMLFormElement, strategyFactory: typeof StrategyFactory) {
        this.form = formElement;
        this.submitButton = formElement.querySelector('button[type="submit"]');
        this.strategy = strategyFactory.createStrategy(formElement);
        this.attachSubmitHandler();
    }

    public setLoadingClass(cssClass: string) {
        this.loadingClass = cssClass;
    }

    private attachSubmitHandler(): void {
        this.form.addEventListener('submit', (event: Event) => {
            event.preventDefault();
            if (!this.form.checkValidity()) {
                event.stopPropagation();
            } else {
                this.handleSubmit();
            }
            this.form.classList.add('was-validated');
        });
    }

    private setLoading(loading: boolean) {
        if (loading && !this.form.classList.contains(this.loadingClass)) {
            this.form.classList.add(this.loadingClass);
            this.addSpinner();
        } else if (this.form.classList.contains(this.loadingClass)) {
            this.form.classList.remove(this.loadingClass);
            this.removeSpinner();
        }
    }

    private addSpinner() {
        if (this.submitButton) {
            this.submitButton.disabled = true;
            this.submitButton.insertAdjacentHTML('afterend', spinnerTemplate);
        }
    }

    private removeSpinner() {
        const spinner = this.form.querySelector('.spinner');
        this.submitButton.disabled = false;
        if (spinner) {
            spinner.remove();
        }
    }

    /**
     * For now display form-success div as defined in form markup
     * or fallback to generic.
     * as needs define we might need to return API response
     */
    private handleSuccess() {
        const success = document.querySelector('.form-success');

        if (success) {
            success.style.display = 'block';
        } else {
            this.form.insertAdjacentHTML('afterend', '<h2>Thank you</h2><p>Your submission has been received successfully.</p>');
        }

        this.form.style.display = 'none';
    }

    private handleError(response: NormalizedApiResponse) {
        const errorHtml = errorTemplate(response.message, response.details);
        const firstField = this.form.querySelector('.form__row');
        firstField.insertAdjacentHTML('beforebegin', errorHtml);
    }

    private removeError() {
        const error = this.form.querySelector('.form__error');
        if (error) {
            error.remove();
        }
    }

    private async handleSubmit(): Promise<void> {
        const formData = new FormData(this.form);

        this.removeError();

        try {
            this.setLoading(true);
            const response: NormalizedApiResponse = await this.strategy.submit(formData);

            if (response.success === true) {
                this.handleSuccess(response);
            } else {
                this.handleError(response);
            }
            this.setLoading(false);
        } catch (error) {
            this.handleError(
                {
                    message: error?.message || 'Something went wrong, please try again.',
                }
            );
            this.setLoading(false);
        }
    }
}
