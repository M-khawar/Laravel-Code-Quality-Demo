<?php

namespace App\Contracts\Repositories;

interface AdminCourseRepositoryInterface
{
    public function fetchCoursesAudience();

    public function fetchAllCourses();

    public function fetchSingleCourse(string $uuid);

    public function createCourse(array $data);

    public function createCourseValidation(array $data);

    public function editCourse(string $courseUuid, array $data);

    public function deleteCourse(string $courseUuid);

    public function editCourseValidation(array $data);

    public function updateCoursePermissions(array $data);

    public function updateCoursePermissionsValidation(array $data);

    public function createSection(array $data);

    public function createSectionValidation(array $data);

    public function editSection(array $data);

    public function editSectionValidation(array $data);

    public function deleteSection(string $sectionUuid);

    public function sortSections(array $data);

    public function sortSectionsValidation(array $data);

    public function createLesson(array $data);

    public function createLessonValidation(array $data);

    public function editLesson(array $data);

    public function editLessonValidation(array $data);

    public function deleteLesson(string $lessonUuid);

    public function sortLessons(array $data);

    public function sortLessonsValidation(array $data);

}
