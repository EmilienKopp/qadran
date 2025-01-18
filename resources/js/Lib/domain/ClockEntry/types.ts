
export type ClockInParams = {
  user_id: number;
  project_id: number;
  in: string | Date;
  timezone: string;
  notes?: string;
};