import { ApiStrategy, NormalizedApiResponse } from '../FormHandler';
import { convertFormDataToJson } from '../helpers';

export interface SpektrixCustomerRequest {
    email: string;
    firstName: string;
    lastName: string;
    AgreedStatements?: string[];
    Tags?: string[];
    [key: `attribute_${string}`]: any;
}

export interface SpektrixApiError {
    errorCode: number;
    message: string;
    problems: string[];
}

/**
 * Spektrix API Strategy class is concerned with the specifics of the
 * spektrix API Request/Response, it is decoupled to avoid mixing concerns
 * with our UI logic
 */
class SpektrixApiStrategy implements ApiStrategy {
    private apiEndpoint: string;

    constructor(apiEndpoint: string) {
        this.apiEndpoint = apiEndpoint;
    }

    async submit(data: FormData): Promise<NormalizedApiResponse> {
        const dataObject = convertFormDataToJson(data);
        const transformedData: SpektrixCustomerRequest = this.transformRequest(dataObject);

        try {
            const response = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: transformedData
            });

            const responseData = await response.json();

            if (!response.ok) {
                return this.handleError(responseData);
            }

            return this.handleResponse(responseData);
        } catch (error) {
            return {
                success: false,
                message: 'An unexpected error occurred. Please try again.',
                data: null,
                details: [error.message]
            };
        }
    }

    /**
     * We don't need to actually deal with the response data for this iteration
     * but I've added this normalization to reduce juggling in the FormHandler
     * class
     */
    handleResponse(response: any): NormalizedApiResponse {
        return {
            success: true,
            message: '',
            data: response?.data || {},
        };
    }

    handleError(error: SpektrixApiError): NormalizedApiResponse {
        return {
            success: false,
            message: error?.message || '',
            data: null,
            details: error?.problems || []
        };
    }

    /**
     * Normalize our request as much as possible and prevent random values from being sent
     * attributes can be quite arbitrary but we know they must start with '_attribute'
     *
     * If client side API requirements extend beyond the basic API sign up form then
     * we would want to move individual requests out of this class
     */
    private transformRequest(formData: { [key: string]: any }): SpektrixCustomerRequest {
        const transformedData: SpektrixCustomerRequest = {
            email: formData.email,
            firstName: formData.firstName,
            lastName: formData.lastName,
            AgreedStatements: this.normalizeToArray(formData.AgreedStatements),
            Tags: this.normalizeToArray(formData.Tags)
        };

        for (const [key, value] of Object.entries(formData)) {
            if (['email', 'firstName', 'lastName', 'AgreedStatements', 'Tags'].includes(key)) {
                continue;
            }

            // Include any field that starts with "attribute_"
            if (key.startsWith('attribute_')) {
                transformedData[key] = value;
            }
        }

        return JSON.stringify(transformedData);
    }

    /**
     * Normalize a form field value to always be an array.
     * This is a Spektrix implementation detail. We want to always
     * treat certain checkbox values as arrays even when only one is
     * selected, unlike the normal checkbox behaviour.
     */
    private normalizeToArray(value: any): string[] {
        if (Array.isArray(value)) {
            return value;
        }
        if (value === undefined) {
            return [];
        }
        return [value];
    }
}

export { SpektrixApiStrategy };
