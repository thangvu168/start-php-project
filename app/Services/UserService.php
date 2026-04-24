<?php

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function getProfile(int $id)
    {
        $data = $this->userRepository->getById($id);

        if (!$data) {
            throw new HttpException('Không tìm thấy người dùng', 404);
        }

        return User::fromArray($data)->toArray();
    }

    public function updateProfile(
        int $id,
        string $firstName,
        string $lastName,
        string $phone,
        ?string $avatarPath = null,
        bool $removeAvatar = false
    ) {
        $data = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'phone'      => $phone,
        ];

        if ($removeAvatar) {
            $data['avatar'] = null;
        } elseif ($avatarPath !== null) {
            $data['avatar'] = $avatarPath;
        }

        return $this->userRepository->update($id, $data);
    }
}
