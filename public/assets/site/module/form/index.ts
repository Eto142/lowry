import { FormHandler } from './FormHandler';
import { AjaxApiStrategyFactory } from './strategies/AjaxApiStrategyFactory';

/**
 * Utility to initialise a FormHandlers for 3rd party APIs
 *
 * @param selector - a valid querySelector string
 * @returns handlers - array of form handler instances
 *
 * @example initialiseForms('[data-api-type="spektrix"])
 */
export const initialiseForms = (selector: string): typeof FormHandler[] => {
    const handlers: typeof FormHandler[] = [];
    const elements = document.querySelectorAll(selector) as HTMLFormElement[];

    if (elements && elements.length > 0) {
        elements.forEach((el) => {
            handlers.push(new FormHandler(el, AjaxApiStrategyFactory));
        });
    }

    return handlers;
};
