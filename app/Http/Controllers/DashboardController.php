<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\DeploymentRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Lang;
use Response;

/**
 * The dashboard controller.
 */
class DashboardController extends Controller
{
    /**
     * The main page of the dashboard.
     *
     * @return View
     * TODO: Use a decorator pattern here
     */
    public function index(
        DeploymentRepositoryInterface $deploymentRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $projects = $projectRepository->getAll();

        $projects_by_group = [];
        foreach ($projects as $project) {
            if (!isset($projects_by_group[$project->group->name])) {
                $projects_by_group[$project->group->name] = [];
            }

            $projects_by_group[$project->group->name][] = $project;
        }

        ksort($projects_by_group);

        return view('dashboard.index', [
            'title'     => Lang::get('dashboard.title'),
            'latest'    => $this->build_timeline_data($deploymentRepository),
            'projects'  => $projects_by_group
        ]);
    }

    /**
     * Returns the timeline
     * 
     * @param DeploymentRepositoryInterface $deploymentRepository
     * @return string
     */
    public function timeline(DeploymentRepositoryInterface $deploymentRepository)
    {
        return view('dashboard.timeline', [
            'latest' => $this->build_timeline_data($deploymentRepository)
        ]);
    }

    /**
     * Builds the data for the timline
     * 
     * @param DeploymentRepositoryInterface $deploymentRepository
     * @return array
     */
    private function build_timeline_data(DeploymentRepositoryInterface $deploymentRepository)
    {
        $deployments = $deploymentRepository->getTimeline();

        $deploys_by_date = [];
        foreach ($deployments as $deployment) {
            $date = $deployment->started_at->format('Y-m-d');

            if (!isset($deploys_by_date[$date])) {
                $deploys_by_date[$date] = [];
            }

            $deploys_by_date[$date][] = $deployment;
        }

        return $deploys_by_date;
    }

    /**
     * Generates an XML file for CCTray.
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @return Response
     */
    public function cctray(ProjectRepositoryInterface $projectRepository)
    {
        $projects = $projectRepository->getAll();

        foreach ($projects as $project) {
            $project->latest_deployment = $project->deployments->first();
        }

        return Response::view('cctray', [
            'projects' => $projects
        ])->header('Content-Type', 'application/xml');
    }
}
