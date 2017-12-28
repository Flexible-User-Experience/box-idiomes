<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\AbstractBaseTest;

/**
 * Class BackendControllerTest.
 *
 * @category Test
 *
 * @author   David Romaní <david@flux.cat>
 */
class BackendControllerTest extends AbstractBaseTest
{
    /**
     * Test admin login request is successful.
     */
    public function testAdminLoginPageIsSuccessful()
    {
        $client = $this->createClient();           // anonymous user
        $client->request('GET', '/admin/login');

        $this->assertStatusCode(200, $client);
    }

    /**
     * Test HTTP request is successful.
     *
     * @dataProvider provideSuccessfulUrls
     *
     * @param string $url
     */
    public function testAdminPagesAreSuccessful($url)
    {
        $client = $this->makeClient(true);         // authenticated user
        $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
    }

    /**
     * Successful Urls provider.
     *
     * @return array
     */
    public function provideSuccessfulUrls()
    {
        return array(
            array('/admin/dashboard'),
            array('/admin/contacts/message/list'),
            array('/admin/contacts/message/1/show'),
            array('/admin/contacts/message/1/answer'),
            array('/admin/contacts/message/1/delete'),
            array('/admin/contacts/newsletter/list'),
            array('/admin/contacts/newsletter/1/show'),
            array('/admin/teachers/teacher/list'),
            array('/admin/teachers/teacher/create'),
            array('/admin/teachers/teacher/1/edit'),
            array('/admin/teachers/absence/list'),
            array('/admin/teachers/absence/create'),
            array('/admin/teachers/absence/1/edit'),
            array('/admin/students/student/list'),
            array('/admin/students/student/create'),
            array('/admin/students/student/1/edit'),
            array('/admin/students/parent/list'),
            array('/admin/students/parent/create'),
            array('/admin/students/parent/1/edit'),
            array('/admin/services/service/list'),
            array('/admin/services/service/create'),
            array('/admin/services/service/1/delete'),
            array('/admin/services/service/1/edit'),
            array('/admin/administrations/province/list'),
            array('/admin/administrations/province/create'),
            array('/admin/administrations/province/1/edit'),
            array('/admin/administrations/city/list'),
            array('/admin/administrations/city/create'),
            array('/admin/administrations/city/1/edit'),
            array('/admin/administrations/bank/list'),
            array('/admin/administrations/bank/create'),
            array('/admin/administrations/bank/1/delete'),
            array('/admin/administrations/bank/1/edit'),
            array('/admin/classrooms/group/list'),
            array('/admin/classrooms/group/create'),
            array('/admin/classrooms/group/1/edit'),
            array('/admin/classrooms/event/list'),
            array('/admin/classrooms/event/create'),
            array('/admin/classrooms/event/1/edit'),
            array('/admin/billings/tariff/list'),
            array('/admin/billings/tariff/create'),
            array('/admin/billings/tariff/1/edit'),
            array('/admin/billings/invoice/list'),
            array('/admin/billings/invoice/create'),
            array('/admin/billings/invoice/1/edit'),
            array('/admin/users/list'),
            array('/admin/users/create'),
            array('/admin/users/1/edit'),
            array('/admin/users/1/delete'),
            array('/admin/full-calendar/load'),
        );
    }

    /**
     * Test HTTP request is not found.
     *
     * @dataProvider provideNotFoundUrls
     *
     * @param string $url
     */
    public function testAdminPagesAreNotFound($url)
    {
        $client = $this->makeClient(true);         // authenticated user
        $client->request('GET', $url);

        $this->assertStatusCode(404, $client);
    }

    /**
     * Not found Urls provider.
     *
     * @return array
     */
    public function provideNotFoundUrls()
    {
        return array(
            array('/admin/contacts/message/create'),
            array('/admin/contacts/message/1/edit'),
            array('/admin/contacts/message/batch'),
            array('/admin/teachers/teacher/1/delete'),
            array('/admin/teachers/teacher/batch'),
            array('/admin/teachers/absence/batch'),
            array('/admin/teachers/absence/1/delete'),
            array('/admin/students/student/batch'),
            array('/admin/students/student/1/delete'),
            array('/admin/students/parent/1/delete'),
            array('/admin/services/service/batch'),
            array('/admin/administrations/province/batch'),
            array('/admin/administrations/province/1/delete'),
            array('/admin/administrations/city/1/delete'),
            array('/admin/classrooms/group/1/delete'),
            array('/admin/classrooms/tariff/1/delete'),
            array('/admin/classrooms/event/1/delete'),
            array('/admin/billings/invoice/1/delete'),
        );
    }
}
