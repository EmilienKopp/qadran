import { router, useForm } from '@inertiajs/svelte';
import { get, Readable } from 'svelte/store';
import type { InertiaForm, Page, RequestPayload, VisitHelperOptions } from './types';
import { page } from '@inertiajs/svelte';

export type * from './types';

export function superUseForm<T extends object>(
  obj?: Partial<T>
): Readable<InertiaForm<T>> {
  return useForm(obj as any);
}

export function hookSuccess<T extends object>(
  form: InertiaForm<T>,
  callback: () => void
): void {
  form.recentlySuccessful && callback();
}

/**
 * Wrapper around inertia router post with preserved state and scroll
 */
export function xPost(route:string, data?: RequestPayload, options?: VisitHelperOptions) {
  return router.post(route, data ?? {}, {
    preserveScroll: true,
    preserveState: true,
    onStart: options?.onStart,
    onProgress: options?.onProgress,
    onFinish: options?.onFinish,
    onSuccess: options?.onSuccess,
    onError: options?.onError,
  });
}

/**
 * @returns The first role of the user
 */
export function getUserRoleName(): string {
  const roles = get(page)?.props.auth.user.roles;
  if(!roles || roles.length === 0) {
    return 'guest';
  }

  if(roles.filter((r: any) => r.name !== 'user').length > 0) {
    return roles.filter((r: any) => r.name !== 'user')[0].name;
  }

  return 'user';
}

/**
 * @returns Array of user roles except the default role
 */
export function getAllUserRoles(): string[] {
  return (
    get(page)
      ?.props.auth.user.roles?.map((role: any) => role.name)
      .filter((r) => r != 'user') ?? []
  );
}
