import type { JobListing } from "$lib/domain/jobListings";
import type { JobApplication } from "$models";

export type JobWithApplication = JobListing & { job_applications: JobApplication[] };
export type Job = JobListing | JobWithApplication;

