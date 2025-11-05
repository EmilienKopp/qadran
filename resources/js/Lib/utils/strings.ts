export function capitalize(str: string) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

export function caseInsensitiveIncludes(haystack?: string, needle?: string) {
    if (!needle?.length) return true;
    if(!haystack?.length) return false;
    return haystack.toLowerCase().includes(needle.toLowerCase());
}

export function leftPad(str: string | number, padChar: string, length: number): string {
    const stringValue = String(str);
    return stringValue.padStart(length, padChar);
}