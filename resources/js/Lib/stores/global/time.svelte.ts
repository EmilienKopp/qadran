import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
import { secondsToHHMM } from '$lib/utils/formatting';

dayjs.extend(duration);

class Clock {
  #datetime = $state(Date.now());

  get date() {
    return dayjs(this.#datetime).format('YYYY-MM-DD');
  }

  get time() {
    return dayjs(this.#datetime).format('HH:mm:ss');
  }

  get epoch() {
    return this.#datetime;
  }

  get h() {
    return dayjs(this.#datetime).format('HH');
  }

  get m() {
    return dayjs(this.#datetime).format('mm');
  }

  get s() {
    return dayjs(this.#datetime).format('ss');
  }

  set(datetime: number) {
    this.#datetime = datetime;
  }

  refresh() {
    this.#datetime = Date.now();
  }

  since(datetime: number) {
    const seconds = dayjs(this.#datetime).diff(datetime, 'seconds');
    return dayjs.duration(seconds, 'seconds').format('HH:mm:ss');
  }
}

export const clock = $state(new Clock());
