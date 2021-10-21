export function humanizeDate(date: string): string {
    let delta: number
    let minute: number
    let hour: number
    let day: number

    delta = Math.round((new Date().getTime() - new Date(date).getTime()) / 1000)
    minute = 60
    hour = minute * 60
    day = hour * 24

    if (delta < 30) {
        return 'току що'
    } else if (delta < minute) {
        return 'преди по-малко от минута'
    } else if (delta < 2 * minute) {
        return 'преди минута'
    } else if (delta < hour) {
        return 'преди ' + Math.floor(delta / minute) + ' минути'
    } else if (Math.floor(delta / hour) == 1) {
        return 'преди час'
    } else if (delta < day) {
        return 'преди ' + Math.floor(delta / hour) + ' часа'
    } else {
        return 'преди повече от 24 часа'
    }
}
