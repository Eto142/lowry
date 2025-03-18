export function convertFormDataToJson(formData: FormData): { [key: string]: any } {
    const formDataObject: { [key: string]: any } = {};
    formData.forEach((value, key) => {
        if (formDataObject[key]) {
            formDataObject[key] = Array.isArray(formDataObject[key])
                ? [...formDataObject[key], value]
                : [formDataObject[key], value];
        } else {
            formDataObject[key] = value;
        }
    });
    return formDataObject;
}

/**
 * Might be needed to help with handling attributes but I don't have any to test with right now
 *
 * See - https://integrate.spektrix.com/docs/apisignup#adding-attributes
 */
export function toPascalCase(str: string): string {
    return str
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join('');
}

export const spinnerTemplate = `
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
`;

export const errorTemplate = (message: string, details: string[] = []) => {
    const formattedDetails = details.join(', ');
    return `
    <div class="form__error">
        <p>${message}. ${formattedDetails}</p>
    </div>
    `;
};
