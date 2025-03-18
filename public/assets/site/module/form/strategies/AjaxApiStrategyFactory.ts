import { SpektrixApiStrategy } from './SpektrixApiStrategy';
import { ApiStrategy } from '../FormHandler';

class AjaxApiStrategyFactory {
    static createStrategy(formElement: HTMLFormElement): ApiStrategy {

        const apiType = formElement.getAttribute('data-api-type');
        const apiEndpoint = formElement.getAttribute('data-api-endpoint');

        if (!apiEndpoint) {
            throw new Error('API endpoint is required');
        }

        switch (apiType) {
        case 'spektrix':
            return new SpektrixApiStrategy(apiEndpoint);

        default:
            throw new Error(`Unknown API type: ${apiType}`);
        }
    }
}

export { AjaxApiStrategyFactory };
