/**
 * Similar to lodash debounce function, saves us including lodash
 *
 * @usage
 *
 * const myFunction = (args) => { // do stuff }
 *
 * const _debounced = debounce(myFunction, 200);
 *
 * _debounced(args)
 *
 */
const debounce = <F extends (...args: any[]) => ReturnType<F>>(
    fn: F,
    delay: number,
) => {
    let timeout: ReturnType<typeof setTimeout>;
    return function(this: ThisParameterType<F>, ...args: Parameters<F>) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            fn.apply(this, args);
        }, delay);
    };
};

export { debounce };
