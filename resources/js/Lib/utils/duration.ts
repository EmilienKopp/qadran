import { leftPad } from "./strings";

export class Duration {
    private totalSeconds = 0;
    private seconds = 0;
    private minutes = 0;
    private hours = 0;

    constructor(seconds: number) {
        this.totalSeconds = seconds;
        this.hours = Math.floor(seconds / 3600);
        this.minutes = Math.floor(seconds / 60) % 60;
        this.seconds = this.totalSeconds - (this.hours * 3600) - (this.minutes * 60);
    }

    public getTotalSeconds(): number {
        return this.totalSeconds;
    }

    public getSeconds(): number {
        return this.seconds;
    }

    public getMinutes(): number {
        return this.minutes;
    }

    public getHours(): number {
        return this.hours;
    }

    public static getMinutes(seconds: number): number {
        const duration = new Duration(seconds);
        return duration.getMinutes();
    }

    public static flooredToMinute(seconds: number): number {
        return Math.floor(seconds / 60) * 60;
    }

    public static nextRoundMinute(seconds: number): number {
        const duration = new Duration(seconds);
        let minutes = duration.getMinutes();
        minutes++;
        return minutes * 60;
    }

    public static getHours(seconds: number): number {
        const duration = new Duration(seconds);
        return duration.getHours();
    }

    public getDays(): number {
        return Math.floor(this.totalSeconds / 86400);
    }

    public toHHMM(): string {
        const hours = this.getHours();
        const minutes = this.getMinutes() % 60;
        return `${leftPad(hours, '0', 2)}:${leftPad(minutes, '0', 2)}`;
    }

    public static toHHMM(seconds: number): string {
        const duration = new Duration(seconds);
        return duration.toHHMM();
    }

    public static toHrMinString(seconds: number): string {
        const duration = new Duration(seconds);
        const hourPart = `${duration.getHours()} hr`;
        const minutesPart = `${duration.getMinutes()} min`;
        return (duration.getHours() > 0) ? `${hourPart} ${minutesPart}` : minutesPart;
    }

    public toHHMMSS(): string {
        const hours = this.getHours();
        const minutes = this.getMinutes() % 60;
        const seconds = this.getSeconds() % 60;

        return `${leftPad(hours, '0', 2)}:${leftPad(minutes, '0', 2)}:${leftPad(seconds, '0', 2)}`;
    }

    public fromNow(): string {
        const now = new Date();
        const then = new Date(now.getTime() + this.totalSeconds * 1000);
        return then.toLocaleTimeString();
    }

    public static fromNow(seconds: number): string {
        const duration = new Duration(seconds);
        return duration.fromNow();
    }
}
