<?php

namespace App\Http\Controllers;

use App\Data\Repositories\NotificationRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

/**
 * Class NotificationController
 * @package App\Http\Controllers\Notification
 *
 * @property NotificationRepository $notification_repo
 */
class NotificationController extends BaseController
{
    protected $notification_repo;

    /**
     * Instantiate the class.
     *
     * @param NotificationRepository $notificationRepo
     */
    public function __construct(
        NotificationRepository $notificationRepo
    ){
        $this->notification_repo = $notificationRepo;
    }

    /**
     * Fetch all notifications.
     *
     * @param Request $request
     * @return NotificationController
     */
    public function fetchAll(Request $request)
    {
        $data = $request->all();
        $data['id'] = $data['auth_id'];
        $data['all'] = true;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is invalid.",
            ]);
        }

        return $this->absorb($this->notification_repo->fetchAllNotifications($data))->json();
    }

    /**
     * Fetch a specific notification based on id.
     *
     * @param Request $request
     * @param integer $id
     * @return NotificationController
     */
    public function fetchNotification(Request $request, $id)
    {
        $data = $request->all();
        $data['notification_id'] = $id;
        $data['id'] = $data['auth_id'];

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is invalid.",
            ]);
        }

        if (!isset($data['notification_id']) ||
            !is_numeric($data['notification_id']) ||
            $data['notification_id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Notification ID is invalid.",
            ]);
        }

        return $this->absorb($this->notification_repo->fetchAllNotifications($data))->json();
    }

    /**
     * Fetch unread notifications.
     *
     * @param Request $request
     * @return NotificationController
     */
    public function fetchUnread(Request $request)
    {
        /**
         * TODO: integrate usage of api tokens
         * $data['id'] = $this->token_ctrl->getUserEntity($request);
         */
        $data = $request->all();
        $data['id'] = $data['auth_id'];
        $data['unread'] = true;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is invalid.",
            ]);
        }

        return $this->absorb($this->notification_repo->fetchAllNotifications($data))->json();
    }

    /**
     * Fetch read notification.
     *
     * @param Request $request
     * @param integer $id
     * @return NotificationController
     */
    public function readNotification(Request $request, $id)
    {
        $data = $request->all();

        $data['id'] = $id;

        return $this->absorb($this->notification_repo->readNotification($data))->json();
    }

    /**
     * Update a notification based on id
     *
     * @param Request $request
     * @param integer $id
     * @return NotificationController
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $data['id'] = $id;

        return $this->absorb($this->notification_repo->defineNotification($data))->json();
    }

    /**
     * Set scheduled notifications.
     *
     * @param Request $request
     * @return NotificationController
     */
    public function scheduledNotifications(Request $request)
    {
        $data = $request->all();

        return $this->absorb($this->notification_repo->scheduledNotifications($data))->json();
    }
}
