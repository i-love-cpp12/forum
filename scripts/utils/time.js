export function getTimeAgo(timestamp)
{
    const diff = (Date.now() - timestamp) / 1000;
    if(diff < 0)
        throw new Error("timestamp must be past time");

    const rtf = new Intl.RelativeTimeFormat("en", {numeric: 'auto'});
    console.log(diff)
    if(Math.floor(diff) === 0)
        return "now";
    else if(diff < 60)
        return `${rtf.format(-Math.floor(diff), 'seconds')}`;
    else if(diff < 60 * 60)
        return `${rtf.format(-Math.floor(diff / 60), 'minutes')}`;
    else if(diff < 60 * 60 * 60)
        return `${rtf.format(-Math.floor(diff / (60 * 60)), 'hours')}`;

    return `${rtf.format(-Math.floor(diff / (60 * 60 * 60)), 'days')}`;
}
