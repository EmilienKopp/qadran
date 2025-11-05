

export function getTimezone() {
    return Intl.DateTimeFormat().resolvedOptions().timeZone;
}

export function listTimezones(): string[] {
    return Intl.supportedValuesOf('timeZone');
}