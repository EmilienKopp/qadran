import type { User as UserModel } from '$models';

export type User = UserModel;

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};

export type UTCDate = Date;
export type JSTDate = Date;
export type Timezone = string;
export type JSTDateTime = Date;
export type UTCDateTime = Date;
export type Hours = number;
export type Minutes = number;
export type Seconds = number;
export type Milliseconds = number;

export type Average = number;
export type Median = number;
export type Mode = number;
export type Range = number;

export type Currency = string;

export type fn = (() => void) | (() => Promise<void>) | EventListener | undefined;

/**
 * Variants for buttons, badges, etc.
 */
export type Variants =
  | 'primary'
  | 'secondary'
  | 'success'
  | 'danger'
  | 'warning'
  | 'info'
  | 'accent'
  | 'neutral'
  | 'ghost'
  | 'error';

/**
 * Toast feedback export types
 */
export type FeedbackType = 'success' | 'error' | 'info';

/**
 * Avoids annoying "Property 'name' does not exist on export type 'EventTarget'" errors
 */
export type AnyEvent =
  | Event
  | CustomEvent
  | KeyboardEvent
  | MouseEvent
  | TouchEvent
  | PointerEvent
  | WheelEvent
  | AnimationEvent
  | TransitionEvent
  | ClipboardEvent
  | CompositionEvent
  | DragEvent
  | FocusEvent
  | InputEvent
  | UIEvent
  | WheelEvent;

export type Listener<T extends AnyEvent = AnyEvent> = (
  event: T
) => void | Promise<void> | EventListener | undefined;

export type Eventable = Document | HTMLElement | Window;

export type HTTPMethod =
  | 'GET'
  | 'POST'
  | 'PUT'
  | 'PATCH'
  | 'DELETE'
  | 'get'
  | 'post'
  | 'put'
  | 'patch'
  | 'delete';

export type DropdownAction = {
  text: string;
  href?: string;
  onclick?: fn;
  method?: HTTPMethod;
  as?: 'a' | 'button';
};

export type SelectOption = {
  value: string | number;
  label?: string;
  name: string;
};

