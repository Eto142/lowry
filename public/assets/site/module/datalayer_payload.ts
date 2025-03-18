declare const moment:any; // global momentjs

/**
 * Build a consistent production/event payload for dataLayer events.
 */
export class DataLayerPayload {
    id: string;
    name: string;
    date: string;
    category: string;
    production: string;

    constructor(id: string, title: string, subtitle: string, date: string, category: string, production: string) {
        this.id = id;
        this.name = title + (subtitle ? ' - ' : '') + subtitle;
        this.date = moment(date, 'YYYY-MM-DD hh:mm:ss').format();
        this.category = category;
        this.production = production;
    }

    build(): Object {
        return {
            item_date: this.date,
            item_name: this.name,
            item_code: this.id, // just for backwards compatibility, item_id is leading
            item_id: this.id,
            item_type: this.category, // just for backwards compatibility, item_category is leading
            item_category: this.category,
            item_production: this.production,
            products: [],
            products_value: 0
        };
    }
}
