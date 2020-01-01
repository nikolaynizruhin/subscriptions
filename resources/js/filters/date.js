export default function (value) {
    Number.isInteger(value) && (value *= 1000)

    return new Date(value).toLocaleDateString("en-GB", { year: 'numeric', month: 'long', day: 'numeric' })
}
