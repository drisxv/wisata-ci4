<?php

namespace App\Controllers;

use App\Models\DestinationModel;
use App\Models\DestinationScheduleModel;
use App\Models\TicketModel;
use CodeIgniter\RESTful\ResourceController;

class Destination extends ResourceController
{
    protected $modelName = DestinationModel::class;
    protected $format    = 'json';

    protected $scheduleModel;
    protected $ticketModel;

    public function __construct()
    {
        $this->scheduleModel = new DestinationScheduleModel();
        $this->ticketModel   = new TicketModel();
    }

    // GET /destinations
    public function index()
    {
        $destinations = $this->model->findAll();

        foreach ($destinations as &$destination) {
            $destination['schedules'] = $this->scheduleModel
                ->where('destination_id', $destination['id'])
                ->findAll();

            $destination['tickets'] = $this->ticketModel
                ->where('destination_id', $destination['id'])
                ->findAll();
        }

        return $this->respond($destinations);
    }

    // GET /destinations/{id}
    public function show($id = null)
    {
        $destination = $this->model->find($id);
        if (!$destination) {
            return $this->failNotFound('Destination not found');
        }

        $destination['schedules'] = $this->scheduleModel
            ->where('destination_id', $id)
            ->findAll();

        $destination['tickets'] = $this->ticketModel
            ->where('destination_id', $id)
            ->findAll();

        return $this->respond($destination);
    }

    // POST /destinations
    public function create()
    {
        $data = [
            'name'        => $this->request->getVar('name'),
            'location'    => $this->request->getVar('location'),
            'description' => $this->request->getVar('description'),
        ];

        if (!$this->model->insert($data)) {
            return $this->fail($this->model->errors());
        }

        $destinationId = $this->model->insertID();

        $schedules = $this->request->getVar('schedules');
        if (is_string($schedules)) {
            $schedules = json_decode($schedules, true);
        }

        if (is_array($schedules)) {
            foreach ($schedules as $schedule) {
                $schedule['destination_id'] = $destinationId;
                $this->scheduleModel->insert($schedule);
            }
        }

        $tickets = $this->request->getVar('tickets');
        if (is_string($tickets)) {
            $tickets = json_decode($tickets, true);
        }

        if (is_array($tickets)) {
            foreach ($tickets as $ticket) {
                $ticket['destination_id'] = $destinationId;
                $this->ticketModel->insert($ticket);
            }
        }

        $data['id'] = $destinationId;
        $data['schedules'] = $schedules ?? [];
        $data['tickets'] = $tickets ?? [];

        return $this->respondCreated($data);
    }

    // PUT /destinations/{id}
    public function update($id = null)
    {
        $destination = $this->model->find($id);
        if (!$destination) {
            return $this->failNotFound('Destination not found');
        }

        $updateData = [
            'name'        => $this->request->getVar('name'),
            'location'    => $this->request->getVar('location'),
            'description' => $this->request->getVar('description'),
        ];

        $this->model->update($id, array_filter($updateData));

        // schedules
        $schedules = $this->request->getVar('schedules');
        if (is_string($schedules)) {
            $schedules = json_decode($schedules, true);
        }

        if (is_array($schedules)) {
            $this->scheduleModel->where('destination_id', $id)->delete();
            foreach ($schedules as $schedule) {
                $schedule['destination_id'] = $id;
                $this->scheduleModel->insert($schedule);
            }
        }

        // tickets
        $tickets = $this->request->getVar('tickets');
        if (is_string($tickets)) {
            $tickets = json_decode($tickets, true);
        }

        if (is_array($tickets)) {
            $this->ticketModel->where('destination_id', $id)->delete();
            foreach ($tickets as $ticket) {
                $ticket['destination_id'] = $id;
                $this->ticketModel->insert($ticket);
            }
        }

        return $this->respondUpdated([
            'id'      => $id,
            'message' => 'Destination updated successfully',
        ]);
    }

    // DELETE /destinations/{id}
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Destination not found');
        }

        // Hapus juga jadwal & tiket terkait
        $this->scheduleModel->where('destination_id', $id)->delete();
        $this->ticketModel->where('destination_id', $id)->delete();
        $this->model->delete($id);

        return $this->respondDeleted([
            'id'      => $id,
            'message' => 'Destination and related data deleted successfully',
        ]);
    }
}
