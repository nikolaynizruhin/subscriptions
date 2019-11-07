export const time = date => {
    const timestamp = date ? + new Date(date) : + new Date

    return Math.floor(timestamp / 1000)
}
