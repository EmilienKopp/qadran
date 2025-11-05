CREATE VIEW daily_logs AS
select
    ce.user_id,
    CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) AS "name",
    u.first_name,
    u.middle_name,
    u.last_name,
    ce.project_id,
    p.name as project_name,
    DATE_TRUNC('day', ce.in AT TIME ZONE 'UTC' AT TIME ZONE u.timezone) AS date,
    sum(
        extract(
        epoch
        from
            coalesce(ce.out, current_timestamp) - ce.in
        )
    ) as total_seconds,
    sum(
        extract(
        epoch
        from
            coalesce(ce.out, current_timestamp) - ce.in
        ) / 60::numeric
    ) as total_minutes
    from
    clock_entries ce
    join users u on u.id = ce.user_id
    join projects p on p.id = ce.project_id
    group by
    ce.user_id,
    ce.project_id,
    CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name),
    u.first_name,
    u.middle_name,
    u.last_name,
    p.name,
    DATE_TRUNC('day', ce.in AT TIME ZONE 'UTC' AT TIME ZONE u.timezone);