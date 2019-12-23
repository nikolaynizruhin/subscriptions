export default function (value) {
    return `${app.stripe.currency_symbol}${value / 100}`
}
